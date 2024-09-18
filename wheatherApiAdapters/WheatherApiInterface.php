<?php

namespace wheatherApiAdapters;

interface WheatherApiInterface {
   public function getWheatherInfo(string $area): array;
}