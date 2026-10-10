<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Rules\PasswordBytes;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

final class CreateAdmin extends Command
{
    protected $signature = 'admin:create';

    protected $description = 'Interactively create an admin using owner-provided credentials';

    public function handle(): int
    {
        if (! $this->input->isInteractive()) {
            $this->error('Run admin:create interactively. Credentials cannot be passed as command arguments.');

            return self::FAILURE;
        }
        $data = [
            'name' => trim((string) $this->ask('Name')),
            'email' => mb_strtolower(trim((string) $this->ask('Email'))),
            // Never fall back to a visible password prompt when the terminal lacks hiding.
            'password' => $this->secret('Password', false),
            'password_confirmation' => $this->secret('Confirm password', false),
        ];
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', new PasswordBytes, Password::min(12)->mixedCase()->numbers()->symbols()],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }

            return self::FAILURE;
        }
        try {
            DB::transaction(function () use ($data) {
                $user = new User;
                $user->fill(['name' => $data['name'], 'email' => $data['email'], 'password' => $data['password']]);
                $user->forceFill(['is_admin' => true])->save();
            });
        } catch (\Throwable) {
            // Do not print SQL bindings: even a failed insert must not expose password hashes.
            $this->error('Admin creation could not be completed. Verify the database connection before retrying.');

            return self::FAILURE;
        }
        $this->info('Admin created. Sign in at /admin/login.');

        return self::SUCCESS;
    }
}
