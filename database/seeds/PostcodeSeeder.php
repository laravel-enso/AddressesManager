<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use LaravelEnso\Addresses\Models\Locality;
use LaravelEnso\Addresses\Models\Postcode;
use LaravelEnso\Addresses\Models\Region;
use LaravelEnso\Countries\Models\Country;
use LaravelEnso\Helpers\Services\JsonReader;
use Symfony\Component\Finder\SplFileInfo;

class PostcodeSeeder extends Seeder
{
    private const Localities = __DIR__.'/../../vendor/laravel-enso/addresses/database/postcodes';

    public function run()
    {
        DB::transaction(fn () => $this->countries()
            ->each(fn (Country $country) => $this->importPostCodes($country)));
    }

    private function countries(): Collection
    {
        return (new Collection(File::files(self::Localities)))
            ->map(fn (SplFileInfo $file) => Country::where('iso_3166_3', $file->getBasename('.json'))->first())
            ->filter();
    }

    private function importPostCodes(Country $country)
    {
        $regions = Region::whereCountryId($country->id)->get()
            ->mapWithKeys(fn ($region) => [$region->abbreviation => $region->id]);

        $localities = Locality::whereHas('region', fn ($query) => $query->whereCountryId($country->id))
            ->get()
            ->mapWithKeys(fn ($locality) => ["{$locality->region_id}_{$locality->name}" => $locality->id]);

        $this->postcodes($country)
            ->filter(fn ($postcode) => isset($regions[$postcode['region']]))
            ->map(fn ($postcode) => [
                'city' => $postcode['city'] ?? null,
                'long' => $postcode['long'] ?? null,
                'lat' => $postcode['lat'] ?? null,
                'code' => $postcode['code'],
                'locality_id' => isset($postcode['locality'])
                    ? $localities->get("{$regions[$postcode['region']]}_{$postcode['locality']}")
                    : null,
                'country_id' => $country->id,
                'region_id' => $regions[$postcode['region']],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ])->chunk(250)
            ->each(fn ($postcodes) => Postcode::insert($postcodes->toArray()));
    }

    private function postcodes(Country $country): Collection
    {
        $fileName = self::Localities.DIRECTORY_SEPARATOR."{$country->iso_3166_3}.json";

        return (new JsonReader($fileName))
            ->collection()
            ->unique(fn ($postcode) => $postcode['code']);
    }
}
