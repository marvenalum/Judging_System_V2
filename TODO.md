# Criteria CRUD Implementation TODO

## Completed:
- [x] Analyze current implementation
- [x] Create plan and get user confirmation
- [x] Update CriteriaController.php
  - [x] Update destroy() method for soft delete
  - [x] Add toggleStatus() method
  - [x] Add score check before deletion
  - [x] Add status filter to index()

- [x] Add toggle route to web.php

- [x] Update resources/views/admin/criteria/index.blade.php
  - [x] Add status filter dropdown
  - [x] Show score count in table
  - [x] Add warning if scores exist
  - [x] Update actions (toggle instead of delete)

- [x] Update resources/views/admin/criteria/create.blade.php
  - [x] Add category_id field
  - [x] Add max_score field

- [x] Update resources/views/admin/criteria/edit.blade.php
  - [x] Add category_id field
  - [x] Add max_score field

## Testing:
- [ ] Test create new criteria
- [ ] Test view criteria
- [ ] Test update criteria
- [ ] Test soft delete (deactivate)
- [ ] Test toggle status
- [ ] Test preventing deletion when scores exist
- [ ] Test status filter
