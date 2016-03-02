<?php

namespace SebWas\Laravel\Eloquent;

trait CompoundKeysRelationship {
	/**
	 * Define a has-many-through relationship.
	 *
	 * @param  string  $related
	 * @param  string  $through
	 * @param  string|null  $firstKey
	 * @param  string|null  $secondKey
	 * @param  string|null  $localKey
	 * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
	 */
	public function hasManyThrough($related, $through, $firstKey = null, $secondKey = null, $localKey = null, $outwardKey = null) {
		$through = new $through;
		$related = new $related;

		$firstKey = $firstKey ?: $this->getForeignKey();

		$secondKey = $secondKey ?: $through->getForeignKey();

		$localKey = $localKey ?: $this->getKeyName();

		$throughUses = class_uses($through);

		if(isset($throughUses["SebWas\Laravel\Eloquent\CompoundKeys"])){
			$outwardKey = $outwardKey ?: $related->getForeignKey();

			return new CompoundKeys\HasManyThrough($related->newQuery(), $this, $through, $firstKey, $secondKey, $localKey, $outwardKey);
		} else {
			return new HasManyThrough($related->newQuery(), $this, $through, $firstKey, $secondKey, $localKey);
		}
	}
}
