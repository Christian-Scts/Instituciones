<?php
namespace App\Services;

use App\Models\Empresa;
use App\Models\PuiToken;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class PuiAuthService
{
    public function autenticarInstitucion(string $institucionId, string $clave): ?Empresa
    {
        $empresa = Empresa::where('rfc', $institucionId)
            ->where('activo', true)
            ->first();

        if (!$empresa || !$empresa->endpoint_password_hash) {
            return null;
        }

        if (!Hash::check($clave, $empresa->endpoint_password_hash)) {
            return null;
        }

        return $empresa;
    }

    public function generarToken(Empresa $empresa): string
    {
        $secret = Crypt::decryptString($empresa->jwt_secret_encrypted);

        $iat = time();
        $exp = $iat + 3600;

        $payload = [
            'institucion_id' => $empresa->rfc,
            'empresa_id' => $empresa->id,
            'iat' => $iat,
            'exp' => $exp,
        ];

        $token = JWT::encode($payload, $secret, $empresa->jwt_algo);

        PuiToken::create([
            'empresa_id' => $empresa->id,
            'token_hash' => hash('sha256', $token),
            'emitido_en' => now(),
            'expira_en' => now()->addHour(),
            'estatus' => 'activo',
        ]);

        $empresa->update(['ultimo_login_ok_en' => now()]);

        return $token;
    }

    public function validarToken(string $jwt): Empresa
    {
        $payload = $this->leerPayloadSinValidar($jwt);

        $empresa = Empresa::whereKey($payload['empresa_id'] ?? 0)->firstOrFail();

        $secret = Crypt::decryptString($empresa->jwt_secret_encrypted);
        JWT::decode($jwt, new Key($secret, $empresa->jwt_algo));

        return $empresa;
    }

    public function leerPayloadSinValidar(string $jwt): array
    {
        $partes = explode('.', $jwt);

        if (count($partes) !== 3) {
            throw new \RuntimeException('Token mal formado');
        }

        return json_decode(base64_decode(strtr($partes[1], '-_', '+/')), true) ?: [];
    }
}