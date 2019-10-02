docker build --squash -t dwoole:base .\base\
docker tag dwoole:base leocavalcante/dwoole:base

docker build --squash -t dwoole:dev .\dev\
docker tag dwoole:dev leocavalcante/dwoole:dev

docker build --squash -t dwoole:prod .\prod\
docker tag dwoole:prod leocavalcante/dwoole:prod
