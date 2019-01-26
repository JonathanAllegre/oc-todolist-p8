Feature: HomePage
  
  Scenario:  Homepage
    Given I am on homepage
    Then I should see "Nom d'utilisateur"

  Scenario: Login
    Given I am on homepage
    When I fill in "username" with "jonathan"
    And I fill in "password" with "test"
    And I press "Se connecter"
    Then I should see "Bienvenue sur Todo List"
    And I should see "Créer une nouvelle tâche"
    And I should see "Consulter la liste des tâches à faire"

  Scenario:
    Given I am on homepage
    When I follow "Créer un utilisateur"
    Then I should see "Tapez le mot de passe à nouveau"