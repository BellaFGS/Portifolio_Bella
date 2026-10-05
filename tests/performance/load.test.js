import { describe, it, expect } from 'vitest';

describe('Teste de Performance - Carregamento Estático', () => {
  it('deve carregar a página principal em menos de 500ms', async () => {
    const start = performance.now();
    const response = await fetch('http://localhost:3000/');
    const duration = performance.now() - start;

    expect(response.status).toBe(200);
    expect(duration).toBeLessThan(500);
  });
});