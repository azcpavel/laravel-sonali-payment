<?php
namespace Exceptio\SonaliPayment\Http\Dtos;

class CreditInformationDto {
    private int $serialNo;
    private string $crAccountOrChallanNo;
    private float $crAmount;
    private string $tranMode;
    private string $onBehalf;

    public function __construct(
    	int $serialNo, 
    	float $crAmount, 
    	string $tranMode, 
    	string $crAccountOrChallanNo = "", 
    	string $onBehalf = ""
    ) {
        // Validate and set properties
        $this->setSerialNo($serialNo);
        $this->setCrAmount($crAmount);
        $this->setTranMode($tranMode);
        $this->setCrAccountOrChallanNo($crAccountOrChallanNo);
        $this->setOnBehalf($onBehalf);
    }

    // Getter methods ...

    public function getSerialNo(): int {
        return $this->serialNo;
    }

    public function getCrAccountOrChallanNo(): string {
        return $this->crAccountOrChallanNo;
    }

    public function getCrAmount(): float {
        return $this->crAmount;
    }

    public function getTranMode(): string {
        return $this->tranMode;
    }

    public function getOnBehalf(): string {
        return $this->onBehalf;
    }

    // Setters with validation methods ...

    private function setSerialNo(int $serialNo): void {
        // Validate if needed
        $this->serialNo = $serialNo;
    }

    private function setCrAccountOrChallanNo(string $crAccountOrChallanNo): void {        
        // Check length limit if provided
        if ($crAccountOrChallanNo !== null && (strlen($crAccountOrChallanNo) < 13 || strlen($crAccountOrChallanNo) > 26)) {
            throw new InvalidArgumentException('CrAccountOrChallanNo must be greater than or equal to 13 and less than or equal to 26 characters.');
        }
        $this->crAccountOrChallanNo = $crAccountOrChallanNo;
    }

    private function setCrAmount(float $crAmount): void {
        // Validate if needed
        $this->crAmount = $crAmount;
    }

    private function setTranMode(string $tranMode): void {
        $allowedModes = ['TRN', 'CHL', 'ACHL'];
        if (!in_array($tranMode, $allowedModes)) {
            throw new InvalidArgumentException('Invalid TranMode. Allowed values: TRN, CHL, ACHL.');
        }
        $this->tranMode = $tranMode;
    }

    private function setOnBehalf(string $onBehalf): void {
        // Check length limit if provided
        if ($onBehalf !== null && strlen($onBehalf) > 120) {
            throw new InvalidArgumentException('OnBehalf must be less than or equal to 120 characters.');
        }
        $this->onBehalf = $onBehalf;
    }
}
?>
