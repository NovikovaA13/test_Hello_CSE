<?php

namespace App\Services;

use App\Http\Requests\ProfilRequest;

class ImageService
{
    /**
     * Chargement d'un image
     * @param ProfilRequest $request
     * @return string
     */
    public function uploadImage(ProfilRequest $request): string
    {
        $image = $request->file('image');
        $filename = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('/assets/images/'), $filename);
        return $filename;
    }
}
