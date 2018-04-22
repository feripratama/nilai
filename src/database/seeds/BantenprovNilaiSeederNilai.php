<?php

use Illuminate\Database\Seeder;

/**
 * Usage :
 * [1] $ composer dump-autoload -o
 * [2] $ php artisan db:seed --class=BantenprovNilaiSeederNilai
 */

class BantenprovNilaiSeederNilai extends Seeder
{
    /* text color */
    protected $RED     ="\033[0;31m";
    protected $CYAN    ="\033[0;36m";
    protected $YELLOW  ="\033[1;33m";
    protected $ORANGE  ="\033[0;33m";
    protected $PUR     ="\033[0;35m";
    protected $GRN     ="\e[32m";
    protected $WHI     ="\e[37m";
    protected $NC      ="\033[0m";

    /* File name */
    /* location : /databse/seeds/file_name.csv */
    protected $fileName = "BantenprovNilaiSeederNilai.csv";

    /* text info : default (true) */
    protected $textInfo = true;

    /* model class */
    protected $model;

    /* __construct */
    public function __construct(){

        $this->model = new Bantenprov\Nilai\Models\Bantenprov\Nilai\Nilai;

    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->insertData();
    }

    /* function insert data */
    protected function insertData()
    {
        /* silahkan di rubah sesuai kebutuhan */
        foreach($this->readCSV() as $data){

            $this->model->create([
                'nomor_un' => $data['nomor_un'],
                'akademik' => $data['akademik'],
                'prestasi' => $data['prestasi'],
                'zona' => $data['zona'],
                'sktm' => $data['sktm'],
                'user_id' => $data['user_id'],
            ]);

            if($this->textInfo){
                echo "============[DATA]============\n";
                $this->orangeText('siswa : ').$this->greenText($data['siswa']);
                echo"\n";
                $this->orangeText('akademik : ').$this->greenText($data['akademik']);
                echo"\n";
                $this->orangeText('prestasi : ').$this->greenText($data['prestasi']);
                echo"\n";
                $this->orangeText('zona : ').$this->greenText($data['zona']);
                echo"\n";
                $this->orangeText('sktm : ').$this->greenText($data['sktm']);
                echo"\n";
                $this->orangeText('user_id : ').$this->greenText($data['user_id']);
                echo"\n";
                echo "============[DATA]============\n\n";
            }

        }

        $this->greenText('[ SEEDER DONE ]');
        echo"\n\n";
    }

    /* text color: orange */
    protected function orangeText($text)
    {
        printf($this->ORANGE.$text.$this->NC);
    }

    /* text color: green */
    protected function greenText($text)
    {
        printf($this->GRN.$text.$this->NC);
    }

    /* function read CSV file */
    protected function readCSV()
    {
        /* Silahkan di rubah sesuai struktur file csv */
        $file = fopen(database_path("seeds/".$this->fileName), "r");
        $all_data = array();
        $row = 1;
        while(($data = fgetcsv($file, 1000, ",")) !== FALSE){
            $all_data[] = [
                           'nomor_un' => $data[0],
                           'akademik' => $data[1],
                           'prestasi' => $data[2],
                           'zona' => $data[3],
                           'sktm' => $data[4],
                           'user_id' => $data[5],
                        ];
        }
        fclose($file);

        return  $all_data;
    }
}
