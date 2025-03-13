<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ShortLinkRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'original_link' => 'required|url',
            'short_link' => ['string', 'min:6', 'max:8', 'unique:short_links,short_link'],
        ];
    }

    protected function prepareForValidation()
    {
        $user = request()->user();

        if (!$user->is_pro) {
            $this->merge([
                'short_link' => null,
            ]);
        }
    }
    public function getRedirectUrl(): string
    {
        return '';
    }
}
