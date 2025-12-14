<?php
class FavoriteController extends Controller
{
    public function toggle($product_id) {
        $user = auth()->user();

        if ($user->favorites()->where('product_id',$product_id)->exists()) {
            $user->favorites()->detach($product_id);
            return ['message'=>'Quitado de favoritos'];
        }

        $user->favorites()->attach($product_id);
        return ['message'=>'Agregado a favoritos'];
    }

    public function list() {
        return auth()->user()->favorites;
    }
}
