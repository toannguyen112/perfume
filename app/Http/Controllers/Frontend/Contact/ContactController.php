<?php

namespace App\Http\Controllers\Frontend\Contact;

use App\Http\Controllers\Controller;
use App\Models\Contact\Contact;
use App\Models\Admin;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Jamstackvietnam\Support\Traits\ApiResponse;
use Jamstackvietnam\Support\Traits\HasApiCrudActions;
use Illuminate\Support\Facades\Storage;
use Jamstackvietnam\Support\Models\File;

class ContactController extends Controller
{
    use HasApiCrudActions, ApiResponse;

    public $model = Contact::class;

    public function store(Request $request)
    {
        $errors = $this->validateRequest(__FUNCTION__);
        if ($data = request()->all()['contact']['data']) {
            if (!isset($data['Họ và tên'])) array_push($errors, 'Họ và tên');
            if (
                !isset($data['Email']) ||
                !$this->validEmail($data['Email'])
            )
                array_push($errors, 'Email');
            if (
                !isset($data['Số điện thoại']) ||
                !$this->validPhoneNumber($data['Số điện thoại'])
            )
                array_push($errors, 'Số điện thoại');
        }

        if (count($errors) > 0) {
            return redirect()->back()->withError($errors);
        }

        $requestData = $request->all()['contact'];

        if (isset($request->all()['contact']['data']['Hồ sơ'])) {

            $files = $request->all()['contact']['data']['Hồ sơ'];

            $fileUploaded = [];

            $uploader = auth()->guard('admin')->user();

            foreach ($files as $file) {
                $fileUploaded[] = File::storeFile($file, $uploader, 'guest');
            }

            foreach ($fileUploaded as $index => $item) {
                $requestData['data']['Hồ sơ ' . $index + 1] = [
                    'Name' => $item['filename'],
                    'Path' => static_url($item->image_url)
                ];
            }

            unset($requestData['data']['Hồ sơ']);

            $this->model::create($requestData);
        } else {

            $this->model::create($request->all()['contact']);
        }

        return redirect()->back()->withSuccess('success');
    }

    public function validEmail($str)
    {
        return (!preg_match("/^([a-z0-9+-]+)(.[a-z0-9+_-]+)*@([a-z0-9-]+.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
    }

    public function validPhoneNumber($str)
    {
        return (!preg_match("/^[+]?[(]?[0-9]{3}[)]?[-\s.]?[0-9]{3}[-\s.]?[0-9]{4,6}$/im", $str)) ? FALSE : TRUE;
    }
}
