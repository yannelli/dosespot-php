<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Auth;

/**
 * Generates the DoseSpot signing key required by the /token endpoint.
 *
 * The output is a 54-character string: the first 22 characters come from
 * the base64-encoded 32-byte seed, followed by the first 32 characters of
 * the base64-encoded SHA-512(seed + clinicKey) digest.
 *
 * If your DoseSpot deployment expects a different signing algorithm,
 * subclass this generator (the seam is the public {@see generate()}
 * method) and pass your custom instance to {@see Authenticator}.
 */
class KeyGenerator
{
    /**
     * Generate the basic-auth-style password used during OAuth grants.
     */
    public function generate(string $clinicKey, ?string $seed = null): string
    {
        $seed ??= $this->randomBytes(32);

        $hash = hash('sha512', $seed.$clinicKey, true);

        $prefix = substr(base64_encode($seed), 0, 22);
        $body = substr(base64_encode($hash), 0, 32);

        return $prefix.$body;
    }

    protected function randomBytes(int $length): string
    {
        return random_bytes($length);
    }
}
