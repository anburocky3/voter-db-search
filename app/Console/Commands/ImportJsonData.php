<?php

     namespace App\Console\Commands;

     use App\Models\VoterData;
     use Illuminate\Console\Command;
     use Illuminate\Support\Facades\DB;
     use Illuminate\Support\Facades\File;

     class ImportJsonData extends Command
     {
         protected $signature = 'import:json-data
                                {--path= : Directory path containing JSON files}
                                {--batch=1000 : Batch size for database inserts}
                                {--truncate : Truncate table before import}';

         protected $description = 'Import voter data from JSON files to database';

         private $importedFiles = 0;
         private $totalRecords = 0;
         private $errors = [];

         public function handle(): int
         {
             $startTime = microtime(true);

             $this->info('Starting JSON data import...');
             $this->newLine();

             // Get directory path from option or use hardcoded default
             $directoryPath = $this->option('path') ?: 'I:\dmk\AC - Samples\AC157';
             $batchSize = (int) $this->option('batch');

             $this->info("Directory: {$directoryPath}");
             $this->info("Batch size: {$batchSize}");
             $this->newLine();

             // Validate directory
             if (!is_dir($directoryPath)) {
                 $this->error("Directory does not exist: {$directoryPath}");
                 return Command::FAILURE;
             }

             // Truncate table if requested
             if ($this->option('truncate')) {
                 if ($this->confirm('This will delete all existing voter data. Continue?')) {
                     $this->info('Truncating voter_data table...');
                     VoterData::truncate();
                     $this->info('Table truncated successfully.');
                     $this->newLine();
                 } else {
                     $this->info('Import cancelled.');
                     return Command::SUCCESS;
                 }
             }

             // Get JSON files
             $jsonFiles = File::glob($directoryPath . '/*.json');
             sort($jsonFiles, SORT_NATURAL | SORT_FLAG_CASE);
             $totalFiles = count($jsonFiles);

             if ($totalFiles === 0) {
                 $this->warn('No JSON files found in the specified directory.');
                 return Command::SUCCESS;
             }

             $this->info("Found {$totalFiles} JSON files to process.");
             $this->newLine();

             // Create progress bar
             $progressBar = $this->output->createProgressBar($totalFiles);
             $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %message%');

             // Process each file
             foreach ($jsonFiles as $filePath) {
                 $fileName = basename($filePath);
                 $progressBar->setMessage("Processing: {$fileName}");

                 try {
                     $recordCount = $this->importJsonFile($filePath, $batchSize);
                     $this->totalRecords += $recordCount;
                     $this->importedFiles++;
                 } catch (\Exception $e) {
                     $this->errors[] = "File: {$fileName} - Error: " . $e->getMessage();
                 }

                 $progressBar->advance();
             }

             $progressBar->finish();
             $this->newLine(2);

             // Show results
             $this->displayResults($startTime, $totalFiles);

             return empty($this->errors) ? Command::SUCCESS : Command::FAILURE;
         }

         private function importJsonFile(string $filePath, int $batchSize): int
         {
             $jsonContent = File::get($filePath);
             $decodedData = json_decode($jsonContent, true);

             if (json_last_error() !== JSON_ERROR_NONE) {
                 throw new \Exception("Invalid JSON: " . json_last_error_msg());
             }

             $records = [];
             $recordCount = 0;

             // Handle both single record and array of records
             $dataToProcess = isset($decodedData[0]) && is_array($decodedData[0]) ? $decodedData : [$decodedData];

             foreach ($dataToProcess as $record) {
                 $records[] = $this->prepareRecord($record);
                 $recordCount++;

                 // Insert in batches
                 if (count($records) >= $batchSize) {
                     $this->insertBatch($records);
                     $records = [];
                 }
             }

             // Insert remaining records
             if (!empty($records)) {
                 $this->insertBatch($records);
             }

             return $recordCount;
         }

         private function insertBatch(array $records): void
         {
             DB::table('voter_data')->insert($records);
         }

         private function prepareRecord(array $record): array
         {
             return [
                 'ST_CODE' => $record['ST_CODE'] ?? null,
                 'AC_NO' => $record['AC_NO'] ?? null,
                 'PART_NO' => $record['PART_NO'] ?? null,
                 'SLNOINPART' => $record['SLNOINPART'] ?? null,
                 'HOUSE_NO_EN' => $record['HOUSE_NO_EN'] ?? null,
                 'SECTION_NO' => $record['SECTION_NO'] ?? null,
                 'FM_NAME_EN' => $record['FM_NAME_EN'] ?? null,
                 'FM_NAME_V1' => $record['FM_NAME_V1'] ?? null,
                 'LASTNAME_EN' => $record['LASTNAME_EN'] ?? null,
                 'LASTNAME_V1' => $record['LASTNAME_V1'] ?? null,
                 'RLN_TYPE' => $record['RLN_TYPE'] ?? null,
                 'RLN_FM_NM_EN' => $record['RLN_FM_NM_EN'] ?? null,
                 'RLN_FM_NM_V1' => $record['RLN_FM_NM_V1'] ?? null,
                 'RLN_L_NM_EN' => $record['RLN_L_NM_EN'] ?? null,
                 'RLN_L_NM_V1' => $record['RLN_L_NM_V1'] ?? null,
                 'IDCARD_NO' => $record['IDCARD_NO'] ?? null,
                 'STATUSTYPE' => $record['STATUSTYPE'] ?? null,
                 'PARTLINKNO' => $record['PARTLINKNO'] ?? null,
                 'SEX' => $record['SEX'] ?? null,
                 'AGE' => $record['AGE'] ?? null,
                 'ORGNLISTNO' => $record['ORGNLISTNO'] ?? null,
                 'ORGN_TYPE' => $record['ORGN_TYPE'] ?? null,
                 'CHNGLISTNO' => $record['CHNGLISTNO'] ?? null,
                 'CHNG_TYPE' => $record['CHNG_TYPE'] ?? null,
                 'WARD_NO' => $record['WARD_NO'] ?? null,
                 'FIELD_3' => $record['FIELD_3'] ?? null,
                 'DLT_REASON' => $record['DLT_REASON'] ?? null,
                 'OLD_HOUSENO' => $record['OLD_HOUSENO'] ?? null,
                 'ADDRESS1_EN' => $record['ADDRESS1_EN'] ?? null,
                 'ADDRESS1_V1' => $record['ADDRESS1_V1'] ?? null,
                 'ADDRESS2_EN' => $record['ADDRESS2_EN'] ?? null,
                 'ADDRESS2_V1' => $record['ADDRESS2_V1'] ?? null,
                 'ADDRESS3_EN' => $record['ADDRESS3_EN'] ?? null,
                 'ADDRESS3_V1' => $record['ADDRESS3_V1'] ?? null,
                 'UIDNO' => $record['UIDNO'] ?? null,
                 'MOBILENO' => $record['MOBILENO'] ?? null,
                 'ac_Name_en' => $record['ac_Name_en'] ?? null,
                 'ac_Name_ta' => $record['ac_Name_ta'] ?? null,
                 'address' => $record['address'] ?? null,
                 'addressta' => $record['addressta'] ?? null,
                 'created_at' => now(),
                 'updated_at' => now(),
             ];
         }

         private function displayResults(float $startTime, int $totalFiles): void
         {
             $processingTime = round(microtime(true) - $startTime, 2);

             $this->info('=== IMPORT SUMMARY ===');
             $this->table(
                 ['Metric', 'Value'],
                 [
                     ['Total JSON files found', $totalFiles],
                     ['Successfully imported files', $this->importedFiles],
                     ['Total records imported', number_format($this->totalRecords)],
                     ['Processing time', "{$processingTime} seconds"],
                     ['Records per second', $processingTime > 0 ? number_format($this->totalRecords / $processingTime, 2) : 'N/A'],
                     ['Errors encountered', count($this->errors)],
                 ]
             );

             if (!empty($this->errors)) {
                 $this->newLine();
                 $this->error('=== ERRORS ===');
                 foreach ($this->errors as $error) {
                     $this->error($error);
                 }
             }

             $this->newLine();
             $this->info('Import completed!');
         }
     }
