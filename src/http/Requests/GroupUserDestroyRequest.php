<?php

namespace Kaw393939\Group\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Kaw393939\Group\Models\Group;

class GroupUserDestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //$user = Auth::user();
        //$group = Group::find(request()->segment(3));

        return Auth::user()->can('destroy-group-users', Group::find(request()->segment(3)));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Name is required!',
        ];
    }
}