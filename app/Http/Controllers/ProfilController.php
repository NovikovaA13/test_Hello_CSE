<?php

namespace App\Http\Controllers;

use App\Enums\StatutEnum;
use App\Http\Requests\ProfilRequest;
use App\Models\Profil;
use App\Services\ImageService;

class ProfilController extends Controller
{

    /**
     * Récupèration des profils
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (auth('sanctum')->check()) {//Aficher les statuts pour admins
            $profils = Profil::all();
        } else {//Sinon afficher que les profils avec les statuts actif
            $profils = Profil::where('statut', StatutEnum::ACTIF)->get();
            $profils->makeHidden('statut');
        }
        $profils->makeHidden(['created_at', 'updated_at']);//On cache les champs qui nous n'interesse pas

        foreach ($profils as $profil) {
            $profil->image = public_path('/assets/images/'.$profil->image);
        }
        return response()->json($profils);
    }

    /**
     * Enregistrement d'un profil
     * @param ProfilRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProfilRequest $request)
    {
        $service = new ImageService();
        $filename = $service->uploadImage($request);
        $profil = new Profil();
        $profil->nom = $request->nom;
        $profil->prenom = $request->prenom;
        $profil->statut = $request->statut;
        $profil->image = $filename;
        $profil->save();

        return response()->json(null,201);
    }


    /**
     * Modification d'un profil
     * @param ProfilRequest $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProfilRequest $request, string $id)
    {
        $service = new ImageService();
        $filename = $service->uploadImage($request);

        $profil = Profil::find($id);
        $profil->nom = $request->nom;
        $profil->prenom = $request->prenom;
        $profil->statut = $request->statut;
        $profil->image = $filename;
        $profil->save();
        return response()->json(null, 204);
    }


    /**
     * Suppression d'un profil
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $profil = Profil::find($id);
        $profil->delete();
        return response()->json(null,204);
    }
}
