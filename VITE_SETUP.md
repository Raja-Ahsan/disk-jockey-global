# Vite Setup Guide

## Development Mode (Recommended)

Run the Vite dev server in a separate terminal:
```bash
npm run dev
```

This will start Vite on `http://127.0.0.1:5173` and watch for file changes.

## Production Mode

If you don't want to run the dev server, build the assets:
```bash
npm run build
```

This will compile all assets to `public/build/` and they'll be served directly without needing the Vite server.

## Troubleshooting

### ERR_CONNECTION_REFUSED
- Make sure Vite dev server is running: `npm run dev`
- Check that port 5173 is not blocked by firewall
- Try building assets instead: `npm run build`

### IPv6 vs IPv4 Issues
- The config is set to use `127.0.0.1` (IPv4) explicitly
- If you still see `[::1]` errors, clear browser cache

### Port Already in Use
- Change the port in `vite.config.js` if 5173 is taken
- Or kill the process using port 5173
