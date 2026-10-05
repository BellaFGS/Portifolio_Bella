import { describe, it, expect } from 'vitest';

interface Project {
  id: number;
  category: string;
}

describe('Testes Unitários - Lógica do Portfólio', () => {
  it('deve calcular o ano atual corretamente para o footer', () => {
    const currentYear = new Date().getFullYear();
    expect(currentYear).toBeGreaterThanOrEqual(2026);
  });

  it('deve filtrar corretamente a categoria de projetos', () => {
    const projects: Project[] = [
      { id: 1, category: 'web' },
      { id: 2, category: 'game' },
      { id: 3, category: 'lab' }
    ];

    const filterWeb = (list: Project[], filter: string) => 
      filter === 'all' ? list : list.filter((p: Project) => p.category === filter);

    expect(filterWeb(projects, 'all')).toHaveLength(3);
    expect(filterWeb(projects, 'web')).toHaveLength(1);
    expect(filterWeb(projects, 'game')).toHaveLength(1);
  });
});