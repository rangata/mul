<?php

namespace App;

use GuzzleHttp\Client;
use http\Env\Response;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $table = 'Resources';
    protected $fillable = [

    ];

    public function studies()
    {
        return $this->hasMany('App\Resource', 'parentId','internalId');
    }

    public function bee()
    {
        return $this->belongsTo('App\Resource', 'internalId', 'parentId');
    }

    public function tags()
    {
        return $this->hasMany('App\MainDicomTags', 'id', 'internalId');
    }

    public function ab()
    {
        return 'weg';
    }

    public function ims($id)
    {
        $client = new Client();
        $respos = $client->get('http://localhost:8042/instances/' . $id . '/preview');
        $im = $respos->getBody()->getContents();

        return base64_encode(response($im)->getContent());
    }
}
