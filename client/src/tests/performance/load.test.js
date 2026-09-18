import autocannon from 'autocannon';

async function runPerformanceTest() {
  const result = await autocannon({
    url: 'http://localhost:3000',
    connections: 10,
    duration: 5, // duração do teste em segundos
  });

  console.log('--- Resultados do Teste de Performance ---');
  console.log(`Requisições/sec: ${result.requests.average}`);
  console.log(`Latência média: ${result.latency.average} ms`);

  // Critério de falha: Se a latência média for superior a 500ms
  if (result.latency.average > 500) {
    console.error('❌ Falha: A latência média do site excedeu 500ms!');
    process.exit(1);
  } else {
    console.log('✅ Teste de performance aprovado!');
  }
}

runPerformanceTest();