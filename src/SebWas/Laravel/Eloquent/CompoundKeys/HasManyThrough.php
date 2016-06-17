<?php

namespace SebWas\Laravel\Eloquent\CompoundKeys;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough as BaseRelation;

class HasManyThrough extends BaseRelation {
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
	public function getQualifiedParentKeyName(){
		return $this->parent->getTable() . '.' . $this->outwardKey;
	}

	/**
	 * Find a related model by its primary key.
	 *
	 * @param  mixed  $id
	 * @param  array  $columns
	 * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|null
	 */
	public function find($id, $columns = ['*'])
	{
		if (is_array($id)) {
			return $this->findMany($id, $columns);
		}

		$relatedKey = $this->getRelated()->qualifyKey($this->getParent()->getForeignKey());

		$this->where($relatedKey, '=', $id);

		return $this->first($columns);
	}

	/**
	 * Find multiple related models by their primary keys.
	 *
	 * @param  mixed  $ids
	 * @param  array  $columns
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function findMany($ids, $columns = ['*'])
	{
		if (empty($ids)) {
			return $this->getRelated()->newCollection();
		}

		$relatedKey = $this->getRelated()->qualifyKey($this->getParent()->getForeignKey());

		$this->whereIn($relatedKey, $ids);

		return $this->get($columns);
	}
}
