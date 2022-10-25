```mermaid
classDiagram

class Artist{
PK - id
name
url
discs
}

Artist -- Disc
class Disc{
PK - id
title
picture
label
Artist
price
}


```