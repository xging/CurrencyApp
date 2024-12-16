<?php
use App\Models\DatabaseCRUD\DatabaseCRUDModel;
use App\Services\CurrencyRateLocalAPI\CurrencyRateRequestService;
use App\Services\CurrencyRateLocalAPI\ValidatorService;
use App\Services\CurrencyRateLocalAPI\DateFormatterService;
use App\Services\CurrencyRateLocalAPI\RequestCleanerService;
use App\Services\CurrencyRateLocalAPI\Interfaces\CurrencyRateRequestInterface;
use App\Services\CurrencyRateLocalAPI\Interfaces\ValidatorInterface;
use App\Services\CurrencyRateLocalAPI\Interfaces\DateFormatterInterface;
use App\Models\DatabaseCRUD\DatabaseCRUDInterface;
use App\Services\CurrencyRateLocalAPI\Interfaces\RequestCleanerInterface;

header('Content-Type: application/json');

class GetCurrencyRatesHistAPI
{
    private CurrencyRateRequestInterface $request;
    private ValidatorInterface $validator;
    private DateFormatterInterface $dateFormatter;
    private DatabaseCRUDInterface $databaseCRUDService;
    private RequestCleanerInterface $requestCleanerService;

    public function __construct(
        CurrencyRateRequestInterface $request,
        ValidatorInterface $validator,
        DateFormatterInterface $dateFormatter,
        DatabaseCRUDInterface $databaseCRUDService,
        RequestCleanerInterface $requestCleanerService
    ) {
        $this->request = $request;
        $this->validator = $validator;
        $this->dateFormatter = $dateFormatter;
        $this->databaseCRUDService = $databaseCRUDService;
        $this->requestCleanerService = $requestCleanerService;
    }

    public function handleApiRequest()
    {
        try {
            $fromCurrency = $this->request->getParam('from');
            $toCurrency = $this->request->getParam('to');
            $_date = $this->request->getParam('date');
            $_time = $this->request->getParam('time');

            if (!$fromCurrency || !$toCurrency || !$_date) {
                throw new Exception('Missing required parameters: from, to, date.');
            }

            $fromCurrency = $this->requestCleanerService->clean($fromCurrency);
            $toCurrency = $this->requestCleanerService->clean($toCurrency);
            $_date = $this->requestCleanerService->clean($_date);
            $_time = $_time ? $this->requestCleanerService->clean($_time) : $_time;


            if (!$this->validator->validateCurrencyCode($fromCurrency) || !$this->validator->validateCurrencyCode($toCurrency)) {
                throw new Exception('Invalid currency format. Use three-letter codes like "USD".');
            }

            $dateTime = $this->dateFormatter->formatDate($_date, $_time);
            $res = $this->dateFormatter->createDateTime($dateTime);
            $date = $res['date'];
            $dateFormat = $res['dateFormat'];
            $formattedDate = $date->format('Y-m-d H:i:s');

            $rate = $this->databaseCRUDService->getCurrencyRatesHist($fromCurrency, $toCurrency, $formattedDate, $dateFormat);

            if (!$rate) {
                throw new Exception("Exchange rate not found for $fromCurrency to $toCurrency at $dateTime.");
            }

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'inputData' => [
                    'from' => $fromCurrency,
                    'to' => $toCurrency,
                    'datetime' => $dateTime,
                ],
                'outputData' => $rate
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

$databaseCRUDModel = new DatabaseCRUDModel();
$currencyRateRequestService = new CurrencyRateRequestService();
$validatorService = new ValidatorService();
$dateFormatterService = new DateFormatterService();
$requestCleanerService = new RequestCleanerService();

$getCurrencyRatesHistAPI = new GetCurrencyRatesHistAPI(
    $currencyRateRequestService,
    $validatorService,
    $dateFormatterService,
    $databaseCRUDModel,
    $requestCleanerService
);

$getCurrencyRatesHistAPI->handleApiRequest();
