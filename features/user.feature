Feature: UserPage

  Scenario: UusersList
    Given I am on "/users"
    Then should see "Liste des utilisateurs"

  Scenario: UserCreate
    Given I am on "/users/create"
    When I fill in "user_username" with "userBehatTest"
    And I fill in "user_password_first" with "test"
    And I fill in "user_password_second" with "test"
    And I fill in "user_email" with "test@testBehat.com"
    And I press "Ajouter"
    Then I should see "Superbe ! L'utilisateur a bien été ajouté."

  Scenario: UserEdit
    Given I am on "/users/2/edit"
    When I fill in "user_username" with "testModifBehate"
    And I press "Modifier"
    Then I should see "L'utilisateur a bien été modifié"
    

