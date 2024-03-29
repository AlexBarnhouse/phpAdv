<?php

namespace GeekBrains\LevelTwo\Person;

class Name
{
    public function __construct(
      private string $firstName,
      private string $lastName
    ){
    }

    /**
     * @return string
     */
    public function first(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirst(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function last(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLast(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function __toString()
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}