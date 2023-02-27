<?php

namespace App\Http\Controllers\Backend\Contact;

use App\Models\Contact\Contact;
use Jamstackvietnam\Support\Traits\HasCrudActions;
use App\Http\Controllers\Controller;

class ApplyController extends Controller
{
    use HasCrudActions;

    public $model = Contact::class;

    public $folder = "Contact/Applies";

    public function filter($query)
    {
        return $query->orWhere('type', Contact::TYPE_JOB_FORM);
    }
}
