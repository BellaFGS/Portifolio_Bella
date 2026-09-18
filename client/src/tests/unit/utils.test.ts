import { describe, it, expect } from 'vitest';

// Exemplo de função utilitária a ser testada
function formatProjectTitle(title: string) {
  return title.trim().toUpperCase();
}

describe('Testes Unitários - Utilitários', () => {
  it('deve formatar o título do projeto para letras maiúsculas', () => {
    const result = formatProjectTitle('  allebstrix dev  ');
    expect(result).toBe('ALLEBSTRIX DEV');
  });
});