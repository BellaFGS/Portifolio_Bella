import { render, screen, fireEvent } from '@testing-library/react';
import { describe, it, expect } from 'vitest';
import React, { useState } from 'react';

// Exemplo de componente testando a integração de estado
function DummyThemeToggle() {
  const [theme, setTheme] = useState('dark');
  return (
    <button onClick={() => setTheme(theme === 'dark' ? 'light' : 'dark')}>
      Tema: {theme}
    </button>
  );
}

describe('Testes de Integração - Tema', () => {
  it('deve alternar o tema ao clicar no botão', () => {
    render(<DummyThemeToggle />);
    const button = screen.getByRole('button');
    
    expect(button).toHaveTextContent('Tema: dark');
    fireEvent.click(button);
    expect(button).toHaveTextContent('Tema: light');
  });
});