Feature: Basic test

  Scenario: Simple compute test
    When I multiply 3 by 5 into a
    Then a should be equal to 15

  Scenario: Another simple compute test
    When I make a test and store a value into aTestVarButWithAnotherName
    Then my var aTestVarButWithAnotherName should be displayed as aTestVarButWithAnotherName

  Scenario: Yet another simple concatenation test
    When I want to concatenate Hello and World into greeting
    Then the concatenation result stored in greeting should be displayed as HelloWorld