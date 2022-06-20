<?php

class Taxicab
{
    private string $filename = 'map.csv';

    private array $map = [];

    private int $distanceMin = 0;

    private array $coords = [0, 0];

    private array $size;

    /**
     *  Constructor
     */
    public function __construct()
    {
        if (!file_exists($this->filename)) {
            exit('ERROR: Cannot find file with coordinates');
        }

        $file = fopen($this->filename, 'r');
        if (!$file) {
            exit('ERROR: Cannot open file with coordinates');
        }

        while (($line = fgetcsv($file)) !== false) {
            $this->map[] = $line;
        }

        fclose($file);

        $this->size['rows'] = count($this->map);
        $this->size['cols'] = count($this->map[0]);
    }

    /**
     * @return void
     */
    public function calculate()
    {
        for ($i = 0; $i < $this->size['rows']; $i++) {
            for ($j = 0; $j < $this->size['cols']; $j++) {
                $distance = $this->calculateWarehouseDistance($i, $j);

                if ($this->distanceMin == 0 || $this->distanceMin > $distance) {
                    $this->distanceMin = $distance;
                    $this->coords = [$i, $j];
                }
            }
        }
    }

    /**
     * @return void
     */
    public function showResults()
    {
        $coordinatesForHumans = [$this->coords[0] + 1, $this->coords[1] + 1];
        $this->writeLine("The warehouse should be located at the coordinates [{$coordinatesForHumans[0]}:{$coordinatesForHumans[1]}]");
        $this->writeLine("Minimum distance total: {$this->distanceMin}");
    }

    /**
     * Calculate summary distance for warehouse
     *
     * @param int $x - required
     * @param int $y - required
     * @return int
     */
    private function calculateWarehouseDistance(int $x, int $y): int
    {
        $distanceSummary = 0;
        for ($i = 0; $i < $this->size['rows']; $i++) {
            for ($j = 0; $j < $this->size['cols']; $j++) {
                if ($i == $x && $j == $y) {
                    continue;
                }

                $distance = $this->getPointsDistance([$x, $y], [$i, $j]);

                //  distance between points * deliveries count * 2 (return back)
                $distanceSummary += $distance * $this->map[$i][$j] * 2;
            }
        }

        return $distanceSummary;
    }

    /**
     * Get taxicab distance between two points
     *
     * @param array $point1 - required
     * @param array $point2 - required
     * @return int
     */
    private function getPointsDistance(array $point1, array $point2): int
    {
        return abs($point1[0] - $point2[0]) + abs($point1[1] - $point2[1]);
    }

    /**
     * Print line to browser
     *
     * @param string $text - required
     * @return void
     */
    private function writeLine(string $text)
    {
        echo $text . '<br/>';
    }
}