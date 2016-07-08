soap-backend
============

My implementation of the Soap Media high-scores backend work sample.


## Data model

1. Name
2. Difficulty
3. Score
4. ID - sequential unique identifier for each score to permit deletion.

Each entry also has a position or rank, which is determined from the score and 
is not part of the data model.


## API

* **GET** `/` - Retrieve the top 10 scores ordered by score.
* **GET** `/num/:n` - Retrieve the top `n` scores.
* **GET** `/name/:name` - Retrieve scores where name matches `name`. Parameter 
  is case-sensitive.
* **GET** `/difficulty/:difficulty` - Retrieve scores at specified difficulty.
  Parameter is case-insensitive.
* **GET** `/sort_by/:first_field(/:second_field)` - Sort the results by up to 
  two fields. Fields are `name`, `difficulty`, and `score`.

All above **GET** endpoints are composable, so you can query for
`/difficulty/hard/num/20/sort_by/name`
to get the top 20 scores for hard mode, sorted by name.

* **POST** `/score` - Accepts `name`, `difficulty`, and `score` as POST 
  parameters and adds the score to the database.
* **DELETE** `/score/:id` - Deletes the score with ID `id`. This requires that 
  the user is authenticated as an admin.


## Notes

1. Ideally users should be able to delete their own scores, but such an 
   authentication system is out-of-scope for this system. It is not known if 
   login names are the same as display names, and thus it is not clear if the 
   name field in the data model is sufficient to determine ownership of a 
   score, or if the data model has to be extended with a login name which is 
   then the target of the authorisation check. Proper implementation of this 
   feature requires reference to the rest of the system.
