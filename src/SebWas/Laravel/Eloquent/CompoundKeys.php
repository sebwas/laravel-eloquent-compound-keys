<?php

namespace SebWas\Laravel\Eloquent;

use Illuminate\Database\Eloquent\Builder;

trait CompoundKeys {
	/**
	 * Qualifies a given key.
	 *
	 * @param  string $key
	 * @return string
	 */
	public function qualifyKey(string $key){
		return $this->getTable().'.'.$key;
	}

	/**
	 * Set the keys for a save update query.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	protected function setKeysForSaveQuery(Builder $query){
		$query->where($this->getKeyForSaveQuery());

		return $query;
	}

	/**
	 * Get the primary key value for a save query.
	 *
	 * @return mixed
	 */
	protected function getKeyForSaveQuery(){
		return $this->getKey();
	}

	/**
	 * Get the value of the model's primary key.
	 *
	 * @return array
	 */
	public function getKey(){
		return array_intersect_key($this->attributes, array_flip($this->getKeyName()));
	}

	/**
	 * Reload a fresh model instance from the database.
	 *
	 * @param  array|string  $with
	 * @return $this|null
	 */
	public function fresh($with = []){
		if (! $this->exists) {
			return;
		}

		return static::with($with)->where($this->getKey())->first();
	}

	/**
	 * Define a one-to-one relationship.
	 *
	 * @param  string  $related
	 * @param  string  $foreignKey
	 * @param  string  $localKey
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function hasOne($related, $foreignKey = null, $localKey = null) {
		$localKey = $localKey ?: with(new $related)->getForeignKey();

		return parent::hasOne($related, $foreignKey, $localKey);
	}

	/**
	 * Define a polymorphic one-to-one relationship.
	 *
	 * @param  string  $related
	 * @param  string  $name
	 * @param  string  $type
	 * @param  string  $id
	 * @param  string  $localKey
	 * @return \Illuminate\Database\Eloquent\Relations\MorphOne
	 */
	public function morphOne($related, $name, $type = null, $id = null, $localKey = null){
		$localKey = $localKey ?: with(new $related)->getForeignKey();

		return parent::morphOne($related, $name, $type, $id, $localKey);
	}

	/**
	 * Define an inverse one-to-one or many relationship.
	 *
	 * @param  string  $related
	 * @param  string  $foreignKey
	 * @param  string  $otherKey
	 * @param  string  $relation
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function belongsTo($related, $foreignKey = null, $otherKey = null, $relation = null){
		$foreignKey = $foreignKey ?: with(new $related)->getForeignKey();

		return parent::belongsTo($related, $foreignKey, $otherKey, $relation);
	}

	/**
	 * Define a one-to-many relationship.
	 *
	 * @param  string  $related
	 * @param  string  $foreignKey
	 * @param  string  $localKey
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function hasMany($related, $foreignKey = null, $localKey = null){
		$localKey = $localKey ?: with(new $related)->getForeignKey();

		return parent::hasMany($related, $foreignKey, $localKey);
	}

	/**
	 * Define a polymorphic one-to-many relationship.
	 *
	 * @param  string  $related
	 * @param  string  $name
	 * @param  string  $type
	 * @param  string  $id
	 * @param  string  $localKey
	 * @return \Illuminate\Database\Eloquent\Relations\MorphMany
	 */
	public function morphMany($related, $name, $type = null, $id = null, $localKey = null){
		$localKey = $localKey ?: with(new $related)->getForeignKey();

		return parent::morphMany($related, $name, $type, $id, $localKey);
	}

	/**
	 * Run the increment or decrement method on the model.
	 *
	 * @param  string  $column
	 * @param  int  $amount
	 * @param  array  $extra
	 * @param  string  $method
	 * @return int
	 */
	protected function incrementOrDecrement($column, $amount, $extra, $method){
		$query = $this->newQuery();

		if (! $this->exists) {
			return $query->{$method}($column, $amount, $extra);
		}

		$this->incrementOrDecrementAttributeValue($column, $amount, $method);

		return $query->where($this->getKey())->{$method}($column, $amount, $extra);
	}

	/**
	 * Insert the given attributes and set the ID on the model.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  array  $attributes
	 * @return void
	 */
	protected function insertAndSetId(Builder $query, $attributes){
		$query->insertGetId($attributes);
	}

	/**
	 * Get the route key for the model.
	 *
	 * @return string
	 */
	public function getRouteKeyName(){
		return '';
	}

	/**
	 * Clone the model into a new, non-existing instance.
	 *
	 * @param  array|null  $except
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function replicate(array $except = null){
		$except = $except ?: array_merge($this->getKeyName(), [
			$this->getCreatedAtColumn(),
			$this->getUpdatedAtColumn(),
		]);

		return parent::replicate($except);
	}

	/**
	 * Define a polymorphic, inverse one-to-one or many relationship.
	 *
	 * @param  string  $name
	 * @param  string  $type
	 * @param  string  $id
	 * @return \Illuminate\Database\Eloquent\Relations\MorphTo
	 */
	public function morphTo($name = null, $type = null, $id = null){
		// TODO
	}

	/**
	 * Destroy the models for the given IDs.
	 *
	 * @param  array|int  $ids
	 * @return int
	 */
	public static function destroy($ids){
		// TODO
	}
}
