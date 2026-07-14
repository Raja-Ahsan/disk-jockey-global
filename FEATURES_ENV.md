# Feature Environment Variables

Add these to your `.env` file:

```env
# Plan My Event — admin notification recipient
PLAN_MY_EVENT_EMAIL=ops@example.com

# Google Calendar OAuth (DJ profiles)
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI="${APP_URL}/dj/dashboard/google-calendar/callback"
```

## Google Cloud setup

1. Create an OAuth 2.0 Client ID (Web application) in Google Cloud Console.
2. Add authorized redirect URI matching `GOOGLE_REDIRECT_URI`.
3. Enable the **Google Calendar API**.
4. Request scopes used by the app: `calendar.readonly`, `userinfo.email`, `openid`.

## Notes

- Tokens are stored encrypted in `dj_google_calendars`.
- If Google Calendar is not connected for a DJ, booking continues without calendar blocking.
- Marketplace catalog is fully separate from Merchandise (`marketplace_*` tables).
- Plan My Event enquiries are stored in `plan_my_event_requests` (never in booking tables).
