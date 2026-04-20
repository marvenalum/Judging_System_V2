# Judging System Scoring Setup Complete

✅ **Active criteria now available!**

## Test Users
| Role | Email | Password | Dashboard |
|------|-------|----------|-----------|
| Judge | judge@test.com | password | /judge/dashboard |
| Participant | participant@test.com | password | /participant/dashboard |
| Admin/Test | test@example.com | (if exists) | /admin/dashboard |

## Test Scoring Flow
1. Login as **judge@test.com / password**
2. Go to **Review Scores** (`/judge/review-scores`)
   - See participants table with criteria columns (Creativity, Content, Delivery)
3. Go to **Score by Category** (`/judge/scoring/category`)
   - Select 'Presentation Skills' → see criteria list + participants
4. Click criteria → bulk score participants

## Data Created
- Event: Sample Judging Event 2024
- Category: Presentation Skills (active)
- 3 Active Criteria: Creativity (20pts), Content Quality (30pts), Delivery (50pts)
- Judge assigned to event
- Participant with reviewed submission ready for scoring

**Scoring works! No more "no active criteria" error.**

Run `php artisan db:seed` anytime to refresh sample data.

