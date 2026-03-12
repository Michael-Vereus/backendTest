note : i didnt put any patch nor update since its my first time uploading it to gh :o.

return msg for debug and catch error: 
return [
    "msg"=>"dbError",
    "error_type" => get_class($e),
    "message" => $e->getMessage(),
    "file" => $e->getFile(),
    "line" => $e->getLine()
];

Patch 2.25.21.01 :
- Updated dbCon.php, removed autoincrement syntax on sqlite query
- Changes action in Case 'Test' ( itemService )
- Added removeItem() in itemsService
- fixed few code in itemServ

Patch 2.25.23.15 : 
- Note to self change to decoupled desig
- Created repository folder for handling interactions and database 
- Created Model for entity modelling and data consistency

Patch 2.27.14.30 : 
- Completed CRUD for Item module
- Input normalization
- Completed All error Handling on all C R U D 
- Implemented Upsert Logic

Small Patch 2.27.15.13 : 
- Starter progres on Bin module
- Added binService and binRepository
- Ran a test, returns '200 OK'

Small Patch 2.27.19.25 : 
- Added masterCont module for accessing db directly will be removed before going public 

Smoll Patch 2.27.22.13 : 
- Tweaked binServ and itemServ.php
- Created the sturc for func addBin in binServ

Patch 2.28.14.50 : 
- Finished getBin functionality
- Started on save functionality

Patch 2.28.19.40 : 
- added fun in binEntity to obj att for DB 
- started and finished making the save module in Bin Repo
- 2 out of 4 module in Bin
- as always added safety nets(trycatch) and ifelse to catch an error 

Smoll Patch 3.1.17.30 : 
- Found a bug on binRepo code specifically fetch functiion
Where $binFetched wont create new objects. Need further investigation

Smoll Patch 3.1.17.38 :
- Fixed bug on binRepo turns out foreach used wrong variables.
Should've used $result but instead used $binFetched.

Patch 3.1.20.40 : 
- Finished creating all CRUD module for BIN.
- Preping creating CRUD module for Stock

Patch 3.2.17.35 : 
- Started creating CRUD module for Stock
- Finished READ module for stock.
- Implementing type hinting for eaiser readability and maintainablity
- Fixing return message to be more consistent across 

Patch 3.2.19.40 : 
- Tried a different approach for generating ID's in stockId
- fixed item collumn naming to have 'item' in it
- adjusted the Item CRUD Module in order to prevent errors in the future.
- implemented jsonSerializable on stockEntity

Patch 3.3.22.15 : 
- Created class for dbCon, for modularity
- Removed dbCon.php added dbConn.php
- Tried interface for all three repos but dropped it felt necessary for this scale. 
- In progress of adding http resposes code in every return statement from repo

Patch 3.4.14.10 : 
- Completed all CRUD Module
- Need a lot of polishing tho in all of module, will complete it it 2 days from now.
- Sketching a Min Max feature into the system (hopes it run)
- This patch only notes the completion of all CRUD module not the polished version !

Smoll Patch 3.6.6.6 : 
- fixed a few error in itemRepo
- fixed the naming incosistency in itemRepo

Smoll Patch 3.6.11.30 : 
- fixed crd module in Item.
- found a bug or a problem in update. When user or incoming json doesnt contain the same id it automatically insert instead of update due to in itemRepo using UPSERT logic combining the two. Need to validate an ID if exist or not.
- Solved inconsistency problem return array itemRepo.

Refactor Patch 3.9.16.15 : 
- Refactored itemRepo class by : 
    - Removing duplicate logics
    - Added a private helper to create placeholder 
    - Added a function to detect affectedRow in db
    - Chaned the return concept from returning the whole array to just return the stuff are ordered to do.

    - Changed the concept thinking so the ssmsApi should do the array JSON structuring not itemRepo nor itemService
- fixed a few typos in itemService
- Added a private function to create itemENtity in itemService


Smoll Patch 3.9.19.45 : 
- Had and idea on interface base repo, scrapped it changed it to a abstract baseRepo :/
- Added a baseRepo abstract, implementing DRY ( Don't Repeat Yourself)

Refactor Patch 3.10.08.35 : 
- Refactored ssmsApi ( main enty point ), divided the switch in a propietary class called apiRouter.
- used DI by creating objects in the class itself 

Smoll Refactor Patch 3.10.23.02 : 
- Created BaseSevice abstract class to inherit some common used functions
- Started working on cleaning up Bin service and repo will document after finished with stock too

Refactor Patch 3.11.08.20 : 
- Added a function in baseService for return consistency
- Added a function in baseService for checkking if a status or return type is true or false to return a string code
- Added function to handle error method request

Refactor Patch 3.12.17.55 : 
- Refactored bin Module ( service and repo ) so it has the same consistency as item module.

Smoll Refactor Patch 3.12.23.52 : 
- fixed some bugs and typos on bin Service
- Added checkId() before updating an item to prevent the creation of another item.
#note to self : 
- add checker or id checker when updating, so the user dont create new items when updating. need to ensure even if the user ask many value to check at once it could still run wo crashing
