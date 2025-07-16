<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generate a secure random password
        $randomPassword = Str::random(12);
        $data['password'] = Hash::make($randomPassword);

        // Send email to the user with their login details
        Mail::to($data['email'])->send(new \App\Mail\UserAccountCreated(
            $data['name'], $data['email'], $randomPassword
        ));

        return $data;
    }
}
