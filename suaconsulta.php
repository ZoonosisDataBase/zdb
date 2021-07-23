<?php
// Criando tabela e cabeçalho de dados:
 echo "<table border=1>";
 echo "<tr>";
 echo "<th>Evolução</th>";
 echo "<th>Zoonose mais recorrente</th>";
 echo "<th>Ocorrências</th>";
 echo "</tr>";
  
 $strcon = mysqli_connect('localhost','root','ZDB','zoonosisdatabasezdb') or die("Ops! Parece que houve um erro na conexão com os dados.");
 $sql = "WITH sub9 (Evolucao, Zoonose, Ocorrencias)
AS (SELECT o.evolucao AS Evolucao, z.doenca AS Zoonose, SUM(o.num_ocorrencia) AS Ocorrencias
FROM ocorrencia AS o
JOIN zoonose AS z
ON o.cod_zoo = z.cod_zoo
JOIN local_ocorrencia AS l
ON o.id_registro = l.id_registro
JOIN ambiente AS a
ON l.cod_amb = a.cod_amb
JOIN classif_pessoal AS c
ON o.id_registro = c.id_registro
WHERE a.regiao = 'sudeste' AND
c.genero = 'feminino' AND
c.classif_etaria = 'jovem' AND
c.escolaridade = 'Superior completo' AND
l.mun_extr_pobreza = 'n'
GROUP BY Evolucao, Zoonose)
SELECT Evolucao, Zoonose, Ocorrencias
FROM (SELECT *, DENSE_RANK() OVER(PARTITION BY sub9.Evolucao ORDER BY sub9.Ocorrencias DESC) AS aux9
FROM sub9) subsub9
WHERE subsub9.aux9 <= 1
ORDER BY subsub9.Evolucao";
 $resultado = mysqli_query($strcon,$sql) or die("Ops! Parece que houve um erro.");
 
 // Obtendo os dados por meio de um loop while
 while ($registro = mysqli_fetch_array($resultado))
 {
    $evol = $registro['Evolucao'];
    $zoo = $registro['Zoonose'];
    $casos = $registro['Ocorrencias'];
    echo "<tr>";
    echo "<td>".$evol."</td>";
    echo "<td>".$zoo."</td>";
    echo "<td>".$casos."</td>";
    echo "</tr>";
 }
 mysqli_close($strcon);
 echo "</table>";
 ?>