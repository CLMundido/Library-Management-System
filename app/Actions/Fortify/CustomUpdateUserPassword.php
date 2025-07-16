<?php

// namespace App\Actions\Fortify;

// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Validator;
// use Laravel\Fortify\Contracts\UpdatesUserPasswords;

// class CustomUpdateUserPassword implements UpdatesUserPasswords
// {
//     public function update($user, array $input)
//     {
//         Validator::make($input, [
//             'current_password' => ['required', 'string'],
//             'password' => ['required', 'string', 'min:8', 'confirmed'],
//         ])->after(function ($validator) use ($user, $input) {
//             if (! Hash::check($input['current_password'], $user->password)) {
//                 $validator->errors()->add('current_password', __('The current password is incorrect.'));
//             }
//         })->validateWithBag('updatePassword');

//         $user->force_password_change = false;
//         $user->password = Hash::make($input['password']);
//         $user->save();
//     }
// }
