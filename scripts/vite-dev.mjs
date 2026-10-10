import { createServer } from 'vite';
import { existsSync, readFileSync, unlinkSync, writeFileSync } from 'node:fs';
import { fileURLToPath } from 'node:url';

// Build-time dev server only. Laravel's @vite reads this standard hot-file URL.
const hotFile = fileURLToPath(new URL('../public/hot', import.meta.url));
const server = await createServer();
await server.listen();
const url = server.resolvedUrls.local[0].replace(/\/$/, '');
writeFileSync(hotFile, url);
const removeHotFile = () => {
    if (existsSync(hotFile) && readFileSync(hotFile, 'utf8') === url) unlinkSync(hotFile);
};
process.once('exit', removeHotFile);
for (const signal of ['SIGINT', 'SIGTERM']) {
    process.once(signal, async () => {
        await server.close();
        process.exit(0);
    });
}
server.printUrls();
