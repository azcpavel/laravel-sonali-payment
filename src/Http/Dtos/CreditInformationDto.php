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
        // Validate if needed
        $this->crAccountOrChallanNo = $crAccountOrChallanNo;
    }

    private function setCrAmount(float $crAmount): void {
        // Validate if needed
        $this->crAmount = $crAmount;
    }

    private function setTranMode(string $tranMode): void {
        // Validate if needed
        $this->tranMode = $tranMode;
    }

    private function setOnBehalf(string $onBehalf): void {
        // Validate if needed
        $this->onBehalf = $onBehalf;
    }
}
?>
