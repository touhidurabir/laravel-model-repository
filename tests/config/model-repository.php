<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Base repository class 
    |--------------------------------------------------------------------------
    |
    | The base repository class that will be extended by all all other generated
    | model repository classes . 
    |
    */

    'base_class' => \Touhidurabir\ModelRepository\BaseRepository::class,


    /*
    |--------------------------------------------------------------------------
    | Default model namespace prefix
    |--------------------------------------------------------------------------
    |
    | The base model path which will be used to get the full namespace of the 
    | give model for which the repository class will be genrated . 
    |
    */

    'models_namespace' => 'App\\Models',


    /*
    |--------------------------------------------------------------------------
    | Path/location namespace to save repository classes
    |--------------------------------------------------------------------------
    |
    | location where to store the repository classes to save/store. 
    |
    */

    'repositories_namespace' => 'App\\Repositories',

];