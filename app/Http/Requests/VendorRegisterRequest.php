<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class VendorRegisterRequest extends FormRequest
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

         // Check if there is already a customer registered with the Customer role.
         $customerExists = User::whereHas('roles', function($query) {
            $query->where('name', 'Customer');
        })->where('email', $this->email)->exists();

        if (!$customerExists) {
            $emailRules[] = 'unique:users';
        }

        // Check if both Customer and Vendor roles are already registered with the given email.
        $vendorOrCustomerBothRolesExists = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['Customer', 'Vendor']);
        })->where('email', $this->email)->exists();

        if ($vendorOrCustomerBothRolesExists) {
            $emailRules[] = 'unique:users';
        }

        return [
            'name' => 'required|string',
            'email' => $emailRules,
            'password' => 'required|string',
            'shop_name' => 'required|string',
        ];
    }
}
