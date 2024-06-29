<?php

namespace App\Http\Controllers;

use App\Enums\StatutEnum;
use App\Http\Requests\ProfilRequest;
use App\Models\Profil;
use App\Services\ImageService;
use Symfony\Component\HttpFoundation\Response;

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
            $profils->makeHidden(['id', 'statut']);//On cache les champs qui nous n'interesse que les admins
        }

        foreach ($profils as $profil) {
            $profil->image = (asset('assets/images/'.$profil->image));//On fait un lien absolute pour les images
        }
        return response()->json(["message" => $profils]);
    }

    /**
     * Enregistrement d'un profil
     * @param ProfilRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProfilRequest $request)
    {
        $service = new ImageService();
        $filename = $service->uploadImage($request);//On traite un image

        $profil = new Profil();
        $profil->nom = $request->nom;
        $profil->prenom = $request->prenom;
        $profil->statut = $request->statut;
        $profil->image = $filename;
        $profil->save();
        return response()->json(null, Response::HTTP_CREATED);
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
        $filename = $service->uploadImage($request);//On traite un image

        $profil = Profil::find($id);
        if ($profil === null) {//si ce profil n'existe pas
            return response()->json(["message" => "Profil avec id $id n'existe pas"], Response::HTTP_BAD_REQUEST);
        }
        $profil->nom = $request->nom;
        $profil->prenom = $request->prenom;
        $profil->statut = $request->statut;
        $profil->image = $filename;
        $profil->save();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }


    /**
     * Suppression d'un profil
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $profil = Profil::find($id);
        if ($profil === null) {//si ce profil n'existe pas
            return response()->json(["message" => "Profil avec id $id n'existe pas"], Response::HTTP_BAD_REQUEST);
        }
        $profil->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
