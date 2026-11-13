<?php
abstract class AbstractController {
    //ATTRIBUT
    private array $models;
    private array $views;

    //CONSTRUCTEUR
    //$models et $views sont des tableaux associatifs
    public function __construct(array $models, array $views){
        foreach($models as $key => $model){
            $this->addModel($key, $model);
        }
        foreach($views as $key => $view){
            $this->addView($key, $view);
        }
    }

    //GETTER, ADDER, REMOVER
    public function getModel(string $key):AbstractModel{
        return $this->models[$key];
    }

    public function addModel(string $key, AbstractModel $model):self{
        $this->models[$key] = $model;
        return $this;
    }

    public function removeModel(string $key):self{
        unset($this->models[$key]);
        return $this;
    }

    public function getView(string $key):View{
        return $this->views[$key];
    }

    public function addView(string $key, View $view):self{
        $this->views[$key] = $view;
        return $this;
    }

    public function removeView(string $key):self{
        unset($this->views[$key]);
        return $this;
    }

    //METHOD
    public abstract function render():void;
}