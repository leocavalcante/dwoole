#!/bin/bash

for tag in base dev prod
do
  docker build --squash -t "dwoole:$tag" "./$tag/"
  docker tag "dwoole:$tag" "leocavalcante/dwoole:$tag"
done
