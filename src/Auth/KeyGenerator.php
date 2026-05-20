<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Auth;

/**
 * Generates the DoseSpot signing key required by the /token endpoint.
 *
 * DoseSpot expects a 32-character base64-encoded value built from a
 * 32-byte random buffer concatenated with the clinic key, then SHA-512
 * hashed. The first character of the random buffer is also used as the
 * base64-encoded password prefix to identify the buffer used.
 */
class KeyGenerator
{
    /**
     * Generate the basic-auth-style password used during OAuth grants.
     */
    public function generate(string $clinicKey, ?string $seed = null): string
    {
        $seed ??= $this->randomBytes(32);

        $hash = hash('sha512', $seed . $clinicKey, true);

        $prefix = substr(base64_encode($seed), 0, 22);
        $body = substr(base64_encode($hash), 0, 32);

        return $prefix . $body;
    }

    protected function randomBytes(int $length): string
    {
        return random_bytes($length);
    }
}
