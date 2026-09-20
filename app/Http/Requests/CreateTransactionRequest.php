<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->current_workspace_id !== null;
    }

    /**
     * Prepare inputs for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('amount')) {
            $amount = (string) $this->amount;
            $amount = str_replace(['R$', ' '], '', $amount);
            // If format is like "1.250,50", remove dots and replace comma with dot
            if (str_contains($amount, ',') && str_contains($amount, '.')) {
                $amount = str_replace('.', '', $amount);
                $amount = str_replace(',', '.', $amount);
            } elseif (str_contains($amount, ',')) {
                $amount = str_replace(',', '.', $amount);
            }

            $this->merge(['amount' => $amount]);
        }

        if ($this->has('is_installment')) {
            $this->merge([
                'is_installment' => filter_var($this->is_installment, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'due_date' => ['required', 'date'],
            'category_id' => ['required', 'exists:categories,id'],
            'account_id' => ['required', 'exists:accounts,id'],
            'type' => ['required', 'in:expense,income'],
            'payment_method' => ['required', 'in:cash,credit_card,pix,debit'],
            'status' => ['required', 'in:paid,pending'],
            'paid_at' => ['nullable', 'date'],
            'is_installment' => ['nullable', 'boolean'],
            'total_installments' => ['nullable', 'required_if:is_installment,true', 'integer', 'min:2', 'max:96'],
        ];
    }

    /**
     * Custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'description.required' => 'A descrição é obrigatória.',
            'amount.required' => 'O valor é obrigatório.',
            'amount.numeric' => 'O valor deve ser numérico.',
            'amount.gt' => 'O valor deve ser maior que zero.',
            'due_date.required' => 'A data de vencimento é obrigatória.',
            'category_id.required' => 'Selecione uma categoria.',
            'account_id.required' => 'Selecione uma conta bancária.',
            'type.required' => 'Selecione o tipo de transação.',
            'total_installments.required_if' => 'Informe o número de parcelas para despesas parceladas.',
            'total_installments.min' => 'O número mínimo de parcelas é 2.',
        ];
    }
}
