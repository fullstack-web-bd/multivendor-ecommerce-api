<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Log;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $emailRules = ['required', 'email'];

        // Check if there is already a user registered with the Vendor role and email.
        $vendorExists = User::whereHas('roles', function($query) {
            $query->where('name', 'Vendor');
        })->where('email', $this->email)->exists();

        if (!$vendorExists) {
            $emailRules[] = 'unique:users';
        }

        // Check if both Customer and Vendor roles are already registered with the given email.
        $customerOrVendorBothRolesExists = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['Customer', 'Vendor']);
        })->where('email', $this->email)->exists();

        if ($customerOrVendorBothRolesExists) {
            $emailRules[] = 'unique:users';
        }

        return [
            'name' => 'required|string',
            'email' => $emailRules,
            'password' => 'required|string',
        ];
    }
}
