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

  Scenario: Click on Se déconnecter button
    Given I am on homepage
    When I fill in "username" with "jonathan"
    And I fill in "password" with "test"
    And I press "Se connecter"
    Then I should see "Se déconnecter"
    When I follow "Se déconnecter"
    Then I should see "Nom d'utilisateur"

  Scenario: Click on Créer une nouvelle tâche
    Given I am on homepage
    When I fill in "username" with "jonathan"
    And I fill in "password" with "test"
    And I press "Se connecter"
    When I follow "Créer une nouvelle tâche"
    Then I should see "Title"
    And I should see "Content"
    And I should see "Retour à la liste des tâches"
    And I should see "Ajouter"
    And I should see "Se Déconnecter"

  Scenario:
    Given I am on homepage
    When I follow "Créer un utilisateur"
    Then I should see "Tapez le mot de passe à nouveau"