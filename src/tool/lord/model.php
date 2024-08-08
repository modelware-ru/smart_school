<?php

namespace ProjectModel;

class Model
{
    public static $model = [];
}

class Atom
{
    public $className;
    public $fileName;
    public $hasLabel = false;
    public $propList = [];
    public $stateList = [];
    public $callbackList = [];

    public function __construct($name)
    {
        $this->className = build_string($name, upperCase: 'all');
        $this->fileName = build_string($name, '_');
        Model::$model[get_class($this)][] = $this;
    }

    public function HasLabel()
    {
        $this->hasLabel = true;
        return $this;
    }

    public function AddProp($name, $default = "''", $comment = '')
    {
        $this->propList[] = [
            'name' => $name,
            'default' => $default,
            'comment' => $comment,
        ];
        return $this;
    }

    public function AddState($name, $default = '', $comment = '')
    {
        $this->stateList[] = [
            'name' => $name,
            'default' => $default,
            'comment' => $comment,
        ];
        return $this;
    }

    public function AddCallback($name, $default = '', $comment = '')
    {
        $this->callbackList[] = [
            'name' => $name,
            'default' => $default,
            'comment' => $comment,
        ];
        return $this;
    }
}


class Page
{
    public $name;
    public $title;
    public $role = '';
    public $component = NULL;
    public $actions = [];
    public $hasSocket = false;

    //
    public $fileName;
    public $className;

    public function __construct($name, $title)
    {
        $this->name = $name;
        $this->title = $title;
        $this->className = buildString($this->name);
        $this->fileName = lcfirst($this->className);
        Model::$model[get_class($this)][] = $this;
    }

    public function Role($role)
    {
        $this->role = $role;
        return $this;
    }

    public function SetComponent(Component $component)
    {
        $this->component = $component;
        $component->Root($this->name);
        return $this;
    }

    public function AddAction(Action $action, $domain, $parameters)
    {
        $this->actions[] = [
            'action' => $action,
            'domain' => $domain,
            'parameters' => $parameters,
        ];
        return $this;
    }

    public function HasSocket($hasSocket = true)
    {
        $this->hasSocket = $hasSocket;
        return $this;
    }
}

class Component
{
    public $name;

    public $root = false;
    public $shared = false;
    public $subShared = false;
    public $widget = false;
    public $scss = false;
    public $scssOpen = false; // component can give the scss outside
    public $parent = NULL;
    public $topParent = NULL;
    public $srcLevel = 0; // path to src
    public $lang = true;

    public $className;
    public $fileName;
    public $folderName;

    public $children = [];

    public function __construct($name = '')
    {
        $this->setName($name);
        Model::$model[get_class($this)][] = $this;
    }

    public function Root($name)
    {
        $this->setName($name);
        $this->root = true;
        $this->srcLevel = 1;
        $this->folderName = $this->className;
        return $this;
    }

    private function setName($name)
    {
        $this->name = $name;
        $this->className = buildString($this->name);
        $this->fileName = lcfirst($this->className);
    }

    public function NoLang()
    {
        $this->lang = false;
        return $this;
    }

    public function Shared()
    {
        $this->shared = true;
        $this->srcLevel = 2;
        $this->folderName = lcfirst($this->className);
        return $this;
    }

    public function SubShared()
    {
        $this->subShared = true;
        return $this;
    }

    public function Widget()
    {
        $this->widget = true;
        $this->srcLevel = 2;
        $this->folderName = lcfirst($this->className);
        return $this;
    }

    public function SCSS($open = false)
    {
        $this->scss = true;
        $this->scssOpen = $open;
        return $this;
    }

    public function GetSrcLevel()
    {
        if (empty($this->parent)) {
            return $this->srcLevel;
        }

        return 1 + $this->parent->GetSrcLevel();
    }

    public function GetFullFileName($isFirst = false)
    {
        if (empty($this->parent)) {
            return $this->className;
        } else if (!$isFirst) {
            return $this->parent->GetFullFileName() . '/' . $this->className;
        } else {
            return $this->parent->GetFullFileName() . '/';
        }
    }

    public function GetFullTypeName()
    {
        if (empty($this->parent)) {
            return $this->className;
        } else {
            return $this->parent->getFullTypeName() . '_' . $this->className;
        }
    }

    public function AddChild(Component $child, $hasRef = false, $refName = '')
    {
        if (!$child->shared && !$child->widget) {
            $child->parent = $this;
            $child->topParent = empty($this->topParent) ? $this : $this->topParent;

            if ($child->topParent->shared || $child->topParent->widget) {
                $child->folderName = $child->parent->folderName . '/' . lcfirst($child->className);
            }
            if ($child->topParent->root) {
                $child->folderName = lcfirst($child->className);
            }
        }

        $this->children[] = [
            'hasRef' => $hasRef,
            'refName' => $refName,
            'child' => $child,
        ];
        return $this;
    }
}

class ItemType
{
    const NOTHING = 0;
    const INT = 1;
    const STRING = 2;
    const BOOLEAN = 3;
}


class Action
{
    public $name;
    public $fileName;
    public $allowedRoles = '';
    public $sessionRequired = true;
    public $middlewareFunc = 'short';
    public $transaction = 'off';
    public $parameters = [];

    public function __construct($name, $fileName = NULL)
    {
        $this->name = $name;
        $this->fileName = empty($fileName) ? $name : $fileName;
        Model::$model[get_class($this)][] = $this;
    }

    public function AllowedRoles($roles)
    {
        $this->allowedRoles = $roles;
        return $this;
    }

    public function NoSessionRequired($sessionRequired = false)
    {
        $this->sessionRequired = $sessionRequired;
        return $this;
    }

    public function MiddlewareFunc($middlewareFunc = 'long')
    {
        $this->middlewareFunc = $middlewareFunc;
        return $this;
    }

    public function Transaction($transaction = 'on')
    {
        $this->transaction = $transaction;
        return $this;
    }

    public function Parameter($name, $type, $value = '')
    {
        $this->parameters[] = [
            'name' => $name,
            'type' => $type,
            'value' => $value,
        ];

        return $this;
    }
}

function buildString($str, $delimiter = '')
{
    $str_array = explode(" ", $str);
    foreach ($str_array as &$val) {
        $val = ucfirst($val);
    }
    return implode($delimiter, $str_array);
}

function build_string($str, $delimiter = '', $upperCase = 'none')
{
    $str_array = explode(" ", $str);
    foreach ($str_array as &$val) {
        if ($upperCase === 'all') {
            $val = ucfirst($val);
        }
    }
    $res = implode($delimiter, $str_array);
    if ($upperCase === 'first') {
        $res = ucfirst($res);
    }
    return $res;
}
