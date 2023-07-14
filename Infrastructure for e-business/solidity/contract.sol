// SPDX-License-Identifier: MIT
pragma solidity ^0.8.11;

contract OrderContract {
    address payable owner;
    address payable customer;
    address payable courier;

    uint256 amount;
    bool transfered;
    bool picked_up;

    constructor(address payable _customer, address payable _owner) {
        customer = _customer;
        owner = _owner;
        courier = payable(address(0));
        transfered = false;
        picked_up = false;
    }

    function getCustomer() external view returns (address payable) {
        return customer;
    }

    function getTransfered() external view returns (bool) {
        return transfered;
    }

    function getPickedUp() external view returns (bool) {
        return picked_up;
    }

    function getCourier() external view returns (address payable) {
        return courier;
    }

    function transferToOwner() public payable {
        owner.transfer(uint((80 * amount) / 100));
    }

    function transferToCourier() public payable {
        courier.transfer(uint((20 * amount) / 100));
    }

    function pay() public payable {
        amount = msg.value;
        transfered = true;
    }

    function pickUp(address payable _courier) public payable {
        courier = _courier;
        picked_up = true;
    }
}
