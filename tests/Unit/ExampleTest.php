<?php

test('that true is true', function () {
    expect(true)->toBeTrue();
});

test('that `Model::withoutRecursion()` method can be called using eval', function () {
    $model = new class extends \Illuminate\Database\Eloquent\Model
    {
        public function toArray(): array
        {
            // Added to prevent IDE to complain about "Undefined variable '$value'" on line 25.
            $result = [];

            eval(<<<'EVAL'

            $result = $this->withoutRecursion(
                fn () => array_merge($this->attributesToArray(), $this->relationsToArray()),
                fn () => $this->attributesToArray(),
            );

            EVAL
            );

            return $result;
        }
    };

    expect($model->toArray())->toEqual([]);
});

test('that `Model::toArray()` method work on mocked models', function () {
    $model = Mockery::mock(\App\Models\User::class)->makePartial();

    expect($model->toArray())->toEqual([]);
});
