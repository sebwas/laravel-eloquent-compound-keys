<?php

namespace SebWas\Laravel\Eloquent\CompoundKeys;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class HasManyThrough extends HasManyThrough {
    /**
     * The outward key on the relationship.
     *
     * @var string
     */
	protected $outwardKey;

    /**
     * Create a new has many through relationship instance.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Database\Eloquent\Model  $farParent
     * @param  \Illuminate\Database\Eloquent\Model  $parent
     * @param  string  $firstKey
     * @param  string  $secondKey
     * @param  string  $localKey
     * @param  string  $outwardKey
     * @return void
     * @override
     */
	public function __construct(Builder $query, Model $farParent, Model $parent, $firstKey, $secondKey, $localKey, $outwardKey){
		$this->outwardKey = $outwardKey;

		parent::__construct($query, $farParent, $parent, $firstKey, $secondKey, $localKey);
	}

    /**
     * Get the fully qualified parent key name.
     *
     * @return string
     * @override
     */
	protected function getParentQualifiedKeyName(){
		return $this->parent->getTable() . $this->outwardKey;
	}
}
